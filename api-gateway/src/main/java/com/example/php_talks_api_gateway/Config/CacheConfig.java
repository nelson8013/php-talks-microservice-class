package com.example.php_talks_api_gateway.Config;

import com.github.benmanes.caffeine.cache.Caffeine;
import org.springframework.cache.CacheManager;
import org.springframework.cache.annotation.EnableCaching;
import org.springframework.cache.caffeine.CaffeineCacheManager;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;

import java.time.Duration;

@Configuration
@EnableCaching
public class CacheConfig {

   @Bean
   public Caffeine<Object, Object> caffeineConfig() {
      return Caffeine.newBuilder()
              .expireAfterWrite(Duration.ofMinutes(10))
              .maximumSize(500);
   }

   @Bean(name = "caffeineCacheManager")
   public CacheManager cacheManager(Caffeine<Object, Object> caffeine) {
      System.out.println("Creating CaffeineCacheManager");
      CaffeineCacheManager cacheManager = new CaffeineCacheManager();
      cacheManager.setCaffeine(caffeine);
      return cacheManager;
   }
}