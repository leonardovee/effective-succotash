package com.leonardovee.effectivesuccotash.presentation.controller

import com.leonardovee.effectivesuccotash.domain.model.Deposit
import com.leonardovee.effectivesuccotash.domain.model.Withdraw
import com.leonardovee.effectivesuccotash.domain.usecase.CreateTransactionUseCase
import com.leonardovee.effectivesuccotash.presentation.resource.TransactionResource
import org.springframework.http.ResponseEntity
import org.springframework.web.bind.annotation.PostMapping
import org.springframework.web.bind.annotation.RequestBody
import org.springframework.web.bind.annotation.RestController
import javax.validation.Valid

@RestController
class TransactionController(private val createTransactionUseCase: CreateTransactionUseCase) {
    @PostMapping("/transactions")
    fun create(@Valid @RequestBody transactionResource: TransactionResource): ResponseEntity<String> {
        val deposit = Deposit(transactionResource.payee, transactionResource.value)
        val withdraw = Withdraw(transactionResource.payer, transactionResource.value)
        createTransactionUseCase.execute(deposit, withdraw)
        return ResponseEntity.ok("")
    }
}