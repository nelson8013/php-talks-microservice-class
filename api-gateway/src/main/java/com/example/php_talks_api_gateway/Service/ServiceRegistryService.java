package com.example.php_talks_api_gateway.Service;

import com.example.php_talks_api_gateway.Model.ServiceRegistry;
import com.example.php_talks_api_gateway.Repository.ServiceRegistryRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Qualifier;
import org.springframework.cache.CacheManager;
import org.springframework.cache.annotation.CacheEvict;
import org.springframework.cache.annotation.CachePut;
import org.springframework.cache.annotation.Cacheable;
import org.springframework.cache.caffeine.CaffeineCacheManager;
import org.springframework.stereotype.Service;

import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Optional;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

@Service
public class ServiceRegistryService {

   private static final String CACHE_KEY = "serviceRegistry";
   private static final Logger logger = LoggerFactory.getLogger(ServiceRegistryService.class);



   private final ServiceRegistryRepository serviceRegistryRepository;


   private final CacheManager  cacheManager;

   @Autowired
   public ServiceRegistryService(ServiceRegistryRepository serviceRegistryRepository,
                                 @Qualifier("caffeineCacheManager") CacheManager  cacheManager) {
      this.serviceRegistryRepository = serviceRegistryRepository;
      this.cacheManager = cacheManager;
   }


   @CachePut(value = CACHE_KEY, key = "#serviceRegistry.name")
   public boolean create(ServiceRegistry serviceRegistry) {
      if (serviceRegistryRepository.existsByName(serviceRegistry.getName())) {
         return false;
      }
      serviceRegistryRepository.save(serviceRegistry);
      updateCache();
      return true;
   }


   @CacheEvict(value = CACHE_KEY, allEntries = true)
   public void flush() {
      logger.info("Flushing all entries from the cache: " + CACHE_KEY);
   }

   public void update(List<ServiceRegistry> services) {
      serviceRegistryRepository.saveAll(services);
      updateCache();
   }

   private void updateCache() {
      List<ServiceRegistry> serviceList = serviceRegistryRepository.findAll();
      Map<String, Map<String, Object>> services = new HashMap<>();
      for (ServiceRegistry service : serviceList) {
         Map<String, Object> serviceData = new HashMap<>();
         serviceData.put("id", service.getId());
         serviceData.put("name", service.getName());
         serviceData.put("url", service.getUrl());
         serviceData.put("port", service.getPort());
         services.put(service.getName(), serviceData);
      }

      if (cacheManager != null && cacheManager.getCache(CACHE_KEY) != null) {
         cacheManager.getCache(CACHE_KEY).put("all", services);
      } else {
         System.err.println("CacheManager or Cache is null. Cannot update cache.");
      }
   }
}
