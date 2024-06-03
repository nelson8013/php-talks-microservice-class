package com.example.php_talks_api_gateway.Service;

import com.example.php_talks_api_gateway.Controller.RoutingController;
import org.springframework.stereotype.Service;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import java.util.Arrays;

@Service
public class RequestProcessingService {

   private static final Logger logger = LoggerFactory.getLogger(RequestProcessingService.class);


   public String getServiceUrlForEndpoint(String endpoint) {
      String serviceName = parseServiceName(endpoint);
      String baseUrl = getServiceBaseUrl(serviceName);

      logger.info("Service Base URL::::" + baseUrl);
      if (baseUrl == null) {
         return null;
      }



      String additionalPath = endpoint.substring(endpoint.indexOf(serviceName) + serviceName.length());
      String processedPath = baseUrl.replaceAll("/$", "") + "/" + additionalPath.replaceAll("^/", "");


      logger.info("PROCESSED PATH::::" + processedPath);
      return processedPath;
   }

   private String getServiceBaseUrl(String serviceName)
   {
      switch (serviceName) {
         case "checkout":
            return "http://127.0.0.1:8002/api/checkout";
         case "inventory":
            return "http://127.0.0.1:8001/api/inventory";
         case "product":
            return "http://127.0.0.1:8000/api/product";
         default:
            return null;
      }
   }

   public static String parseServiceName(String endpoint) {
      String[] segments = endpoint.split("/");
      logger.info("Parsed segments:::" + Arrays.toString(segments));
      return segments.length > 3 ? segments[3] : null;
   }
}
