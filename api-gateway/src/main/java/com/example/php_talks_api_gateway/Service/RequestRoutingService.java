package com.example.php_talks_api_gateway.Service;

import com.example.php_talks_api_gateway.DataTransferObjects.RequestResponse;
import jakarta.servlet.http.HttpServletRequest;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.*;
import org.springframework.stereotype.Service;
import org.springframework.web.client.RestTemplate;

import java.util.Enumeration;

@Service
public class RequestRoutingService {

   @Autowired
   private RequestProcessingService processingService;

   @Autowired
   private RestTemplate restTemplate;

   public RequestResponse router(HttpServletRequest request){
      String endpoint = request.getRequestURI();

      String serviceUrl = processingService.getServiceUrlForEndpoint(endpoint);

      if (serviceUrl != null) {
         HttpHeaders headers = new HttpHeaders();
         Enumeration<String> headerNames = request.getHeaderNames();
         while (headerNames.hasMoreElements()) {
            String headerName = headerNames.nextElement();
            headers.add(headerName, request.getHeader(headerName));
         }

         HttpEntity<String> entity = new HttpEntity<>(headers);
         HttpMethod httpMethod = HttpMethod.valueOf(request.getMethod());

         ResponseEntity<String> response = restTemplate.exchange(serviceUrl, httpMethod, entity, String.class);
         return new RequestResponse(response.getStatusCodeValue(),response.getBody());
      } else {
         return new RequestResponse(HttpStatus.NOT_FOUND.value(), "{\"error\": \"Service URL not found for endpoint\"}");
      }
   }
}
