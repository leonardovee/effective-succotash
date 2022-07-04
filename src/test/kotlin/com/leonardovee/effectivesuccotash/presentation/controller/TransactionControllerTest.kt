package com.leonardovee.effectivesuccotash.presentation.controller

import com.fasterxml.jackson.databind.ObjectMapper
import com.leonardovee.effectivesuccotash.presentation.resource.TransactionResource
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
        val transactionResource = TransactionResource("any@email.com", "other@email.com", "10.00")
        mockMvc.perform(
            post("/transactions").contentType(MediaType.APPLICATION_JSON)
                .content(objectMapper.writeValueAsString(transactionResource))
        ).andExpect(status().isOk)
    }

    @Test
    fun `should return bad request if validation fails`() {
        data class NullableTransactionResource(
            val payer: String?, val payee: String?, val value: String?
        )

        val transactionResource = NullableTransactionResource(null, null, null)
        mockMvc.perform(
            post("/transactions").contentType(MediaType.APPLICATION_JSON)
                .content(objectMapper.writeValueAsString(transactionResource))
        ).andExpect(status().isBadRequest)
    }
}