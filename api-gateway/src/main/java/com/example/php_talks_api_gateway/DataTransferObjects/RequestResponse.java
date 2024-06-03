package com.example.php_talks_api_gateway.DataTransferObjects;

import lombok.Getter;
import lombok.Setter;

public class RequestResponse {
   private int statusCode;
   private String body;

   public RequestResponse(int statusCode, String body) {
      this.statusCode = statusCode;
      this.body = body;
   }

   public int getStatusCode() {
      return statusCode;
   }

   public String getBody() {
      return body;
   }

   public void setStatusCode(int statusCode) {
      this.statusCode = statusCode;
   }

   public void setBody(String body) {
      this.body = body;
   }
}
