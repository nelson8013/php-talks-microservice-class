package com.example.php_talks_api_gateway.Repository;

import com.example.php_talks_api_gateway.Model.ServiceRegistry;
import org.springframework.data.jpa.repository.JpaRepository;

import java.util.Optional;

public interface ServiceRegistryRepository extends JpaRepository<ServiceRegistry, Long> {
   Optional<ServiceRegistry> findByName(String name);
   boolean existsByName(String name);
}