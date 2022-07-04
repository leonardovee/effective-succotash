package com.leonardovee.effectivesuccotash.presentation.controller

import org.junit.jupiter.api.Assertions.*
import org.junit.jupiter.api.Test
import org.springframework.beans.factory.annotation.Autowired
import org.springframework.boot.test.context.SpringBootTest

@SpringBootTest
internal class TransactionControllerTest {
    @Autowired
    private lateinit var transactionController: TransactionController

    @Test
    fun `should load the application context`() {
        assertNotNull(transactionController)
    }
}