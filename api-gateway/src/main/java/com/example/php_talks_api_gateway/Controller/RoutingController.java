package com.example.php_talks_api_gateway.Controller;

import com.example.php_talks_api_gateway.DataTransferObjects.RequestResponse;
import com.example.php_talks_api_gateway.Service.RequestProcessingService;
import com.example.php_talks_api_gateway.Service.RequestRoutingService;
import com.example.php_talks_api_gateway.Service.ServiceRegistryService;
import jakarta.servlet.http.HttpServletRequest;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.*;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;
import org.springframework.web.client.RestTemplate;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import java.util.Enumeration;


@RestController
@RequestMapping("/api/router")
public class RoutingController {

   private static final Logger logger = LoggerFactory.getLogger(RoutingController.class);


   @Autowired
   private RestTemplate restTemplate;

   @Autowired
   private RequestRoutingService routingService;

   @Autowired
   private RequestProcessingService processingService;


   @RequestMapping("/**")
   public ResponseEntity<String> route(HttpServletRequest request) {
      RequestResponse customResponse = routingService.router(request);
      return ResponseEntity.status(customResponse.getStatusCode()).body(customResponse.getBody());
   }
}


