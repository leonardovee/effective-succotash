package com.leonardovee.effectivesuccotash.presentation.controller

import com.fasterxml.jackson.databind.ObjectMapper
import org.junit.jupiter.api.Assertions.assertNotNull
import org.junit.jupiter.api.Test
import org.springframework.beans.factory.annotation.Autowired
import org.springframework.boot.test.autoconfigure.web.servlet.WebMvcTest
import org.springframework.http.MediaType
import org.springframework.test.web.servlet.MockMvc
import org.springframework.test.web.servlet.request.MockMvcRequestBuilders.post
import org.springframework.test.web.servlet.result.MockMvcResultMatchers.status

@WebMvcTest(controllers = [TransactionController::class])
internal class TransactionControllerTest {
    @Autowired
    private lateinit var mockMvc: MockMvc

    @Autowired
    private lateinit var objectMapper: ObjectMapper

    @Autowired
    private lateinit var transactionController: TransactionController

    @Test
    fun `should load the application context`() {
        assertNotNull(transactionController)
    }

    @Test
    fun `should return ok`() {
        mockMvc.perform(post("/transactions").contentType(MediaType.APPLICATION_JSON)).andExpect(status().isOk)
    }
}