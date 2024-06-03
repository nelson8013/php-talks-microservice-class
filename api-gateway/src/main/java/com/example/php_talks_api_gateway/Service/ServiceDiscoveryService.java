package com.example.php_talks_api_gateway.Service;

import com.example.php_talks_api_gateway.Model.ServiceRegistry;
import com.example.php_talks_api_gateway.Repository.ServiceRegistryRepository;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Qualifier;
import org.springframework.cache.CacheManager;
import org.springframework.cache.annotation.Cacheable;
import org.springframework.stereotype.Service;
import org.springframework.web.client.RestTemplate;

import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Optional;

@Service
public class ServiceDiscoveryService {

   private static final String CACHE_KEY = "serviceRegistry";
   private static final Logger logger = LoggerFactory.getLogger(ServiceRegistryService.class);
   private final RestTemplate restTemplate;

   private final ServiceRegistryRepository serviceRegistryRepository;

   private final CacheManager cacheManager;

   @Autowired
   public ServiceDiscoveryService(RestTemplate restTemplate, ServiceRegistryRepository serviceRegistryRepository, @Qualifier("caffeineCacheManager") CacheManager  cacheManager) {
      this.restTemplate = restTemplate;
      this.serviceRegistryRepository = serviceRegistryRepository;
      this.cacheManager = cacheManager;
   }


   @Cacheable(value = CACHE_KEY, key = "'all'")
   public Map<String, Map<String, Object>> services() {

      List<ServiceRegistry> serviceList = serviceRegistryRepository.findAll();
      Map<String, Map<String, Object>> services = new HashMap<>();

      for (ServiceRegistry service : serviceList) {
         Map<String, Object> serviceData = new HashMap<>();

         serviceData.put("name", service.getName());
         serviceData.put("url", service.getUrl());
         serviceData.put("port", service.getPort());
         services.put(service.getName(), serviceData);
      }
      return services;
   }


   @Cacheable(value = CACHE_KEY, key = "#serviceName")
   public Map<String, Object> getRegistryByName(String serviceName) {
      Optional<ServiceRegistry> serviceRegistry = serviceRegistryRepository.findByName(serviceName);

      if (serviceRegistry.isPresent()) {
         ServiceRegistry service = serviceRegistry.get();
         Map<String, Object> serviceData = new HashMap<>();

         serviceData.put("id", service.getId());
         serviceData.put("name", service.getName());
         serviceData.put("url", service.getUrl());
         serviceData.put("port", service.getPort());

         return serviceData;
      }
      return null;
   }


   public Map<String, Map<String, Object>> checkServicesHealth() {
      restTemplate.getForObject("http://127.0.0.1:5634/api/gate/services", Map.class);
      Map<String, Map<String, Object>> services = getCachedServices();

      if (services == null) {
         System.out.println("No services found in cache.");
         return new HashMap<>();
      }

      // Perform health check for each service
      services.forEach((serviceName, serviceData) -> {
         String healthCheckUrl = serviceData.get("url").toString().replaceAll("/$", "") + "/health";
         try {
            Map<String, String> response = restTemplate.getForObject(healthCheckUrl, Map.class);
            if (response != null && "healthy".equals(response.get("status"))) {
               serviceData.put("status", "healthy");
               serviceData.put("message", response.get("message"));
               System.out.println("Health check for service " + serviceName + " at " + healthCheckUrl + ": " + response);
            } else {
               serviceData.put("status", "unhealthy");
               serviceData.put("message", response != null ? response.get("message") : "No response message");
               System.out.println("Health check for service " + serviceName + " at " + healthCheckUrl + " failed.");
            }
         } catch (Exception e) {
            serviceData.put("status", "unhealthy");
            serviceData.put("message", "Error: " + e.getMessage());
            System.err.println("Error occurred while checking health of service " + serviceName + " at " + healthCheckUrl + ": " + e.getMessage());
         }
      });

      // Update cache with new statuses
      updateCache(services);

      return services;
   }


   private Map<String, Map<String, Object>> getCachedServices() {
      return cacheManager.getCache(CACHE_KEY).get("all", Map.class);
   }

   private void updateCache(Map<String, Map<String, Object>> services) {
      cacheManager.getCache(CACHE_KEY).put("all", services);
   }


}
