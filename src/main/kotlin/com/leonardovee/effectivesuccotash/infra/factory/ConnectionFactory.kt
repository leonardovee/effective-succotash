package com.leonardovee.effectivesuccotash.infra.factory

import org.springframework.stereotype.Component
import java.sql.Connection
import java.sql.DriverManager

@Component
class ConnectionFactory {
    fun build(): Connection =
        DriverManager.getConnection("jdbc:postgresql://localhost:5432/effective-succotash", "postgres", "postgres")
}