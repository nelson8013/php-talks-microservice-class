package com.example.php_talks_api_gateway.Controller;


import com.example.php_talks_api_gateway.Model.ServiceRegistry;
import com.example.php_talks_api_gateway.Service.ServiceRegistryService;
import com.example.php_talks_api_gateway.Service.ServiceDiscoveryService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.*;

import java.util.List;
import java.util.Map;

@RestController
@RequestMapping("/api/gate")
public class GateWayController {
   @Autowired
   private ServiceRegistryService serviceRegistryService;
   @Autowired
   private ServiceDiscoveryService serviceDiscoveryService;




   @GetMapping("/services")
   public Map<String, Map<String, Object>> getAllServices() {
      return serviceDiscoveryService.services();
   }

   @PostMapping("/register")
   public boolean createService(@RequestBody ServiceRegistry serviceRegistry) {
      return serviceRegistryService.create(serviceRegistry);
   }

   @GetMapping("/{serviceName}")
   public Map<String, Object> getServiceByName(@PathVariable String serviceName) {
      return serviceDiscoveryService.getRegistryByName(serviceName);
   }

   @DeleteMapping("/flush")
   public void flushCache() {
      serviceRegistryService.flush();
   }

   @GetMapping("/check")
   public Map<String, Map<String, Object>> checkServicesHealth() {
      return serviceDiscoveryService.checkServicesHealth();
   }

   @PutMapping
   public void updateServices(@RequestBody List<ServiceRegistry> services) {
      serviceRegistryService.update(services);
   }
}
