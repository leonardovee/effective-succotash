package com.leonardovee.effectivesuccotash.presentation.controller

import com.fasterxml.jackson.databind.ObjectMapper
import com.leonardovee.effectivesuccotash.domain.model.Deposit
import com.leonardovee.effectivesuccotash.domain.model.Transaction
import com.leonardovee.effectivesuccotash.domain.model.Withdraw
import com.leonardovee.effectivesuccotash.domain.usecase.CreateTransactionUseCase
import com.leonardovee.effectivesuccotash.presentation.resource.TransactionRequestResource
import org.junit.jupiter.api.Assertions.assertNotNull
import org.junit.jupiter.api.BeforeEach
import org.junit.jupiter.api.Test
import org.mockito.Mockito.*
import org.springframework.beans.factory.annotation.Autowired
import org.springframework.boot.test.autoconfigure.web.servlet.WebMvcTest
import org.springframework.boot.test.mock.mockito.MockBean
import org.springframework.http.MediaType
import org.springframework.test.web.servlet.MockMvc
import org.springframework.test.web.servlet.request.MockMvcRequestBuilders.post
import org.springframework.test.web.servlet.result.MockMvcResultMatchers.content
import org.springframework.test.web.servlet.result.MockMvcResultMatchers.status

@WebMvcTest(controllers = [TransactionController::class])
internal class TransactionControllerTest {
    @Autowired
    private lateinit var mockMvc: MockMvc

    @Autowired
    private lateinit var objectMapper: ObjectMapper

    @Autowired
    private lateinit var transactionController: TransactionController

    @MockBean
    private lateinit var createTransactionUseCase: CreateTransactionUseCase

    private var transactionRequestResource = TransactionRequestResource("any@email.com", "other@email.com", "10.00")

    private var deposit = Deposit(transactionRequestResource.payee, transactionRequestResource.value)

    private var withdraw = Withdraw(transactionRequestResource.payer, transactionRequestResource.value)

    @BeforeEach
    fun setUp() {
        `when`(createTransactionUseCase.execute(deposit, withdraw)).thenReturn(
            Transaction(
                "2a58a6be-f4c3-4b3d-bf09-9765b583cf10",
                "669cafbc-207a-46d7-880e-040bd65dd1a7",
                "e8cf0fcc-2476-458a-aea8-befda657b808"
            )
        )
    }

    @Test
    fun `should load the application context`() {
        assertNotNull(transactionController)
    }

    @Test
    fun `should return ok`() {
        val transactionRequestResource = TransactionRequestResource("any@email.com", "other@email.com", "10.00")
        mockMvc.perform(
            post("/transactions").contentType(MediaType.APPLICATION_JSON)
                .content(objectMapper.writeValueAsString(transactionRequestResource))
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

    @Test
    fun `should call create transaction use case`() {
        mockMvc.perform(
            post("/transactions").contentType(MediaType.APPLICATION_JSON)
                .content(objectMapper.writeValueAsString(transactionRequestResource))
        ).andExpect(status().isOk)

        verify(createTransactionUseCase, times(1)).execute(deposit, withdraw)
    }

    @Test
    fun `should return the transaction id`() {
        mockMvc.perform(
            post("/transactions").contentType(MediaType.APPLICATION_JSON)
                .content(objectMapper.writeValueAsString(transactionRequestResource))
        ).andExpect(status().isOk).andExpect(content().string("{\"id\":\"2a58a6be-f4c3-4b3d-bf09-9765b583cf10\"}"))
    }
}