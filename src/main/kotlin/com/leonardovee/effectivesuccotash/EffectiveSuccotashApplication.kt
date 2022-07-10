package com.leonardovee.effectivesuccotash

import org.springframework.boot.autoconfigure.SpringBootApplication
import org.springframework.boot.runApplication
import org.springframework.context.annotation.ComponentScan

@SpringBootApplication
@ComponentScan("com.leonardovee.effectivesuccotash")
class EffectiveSuccotashApplication

fun main(args: Array<String>) {
    runApplication<EffectiveSuccotashApplication>(*args)
}
