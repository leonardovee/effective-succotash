package com.leonardovee.effectivesuccotash.presentation.controller

import com.leonardovee.effectivesuccotash.domain.model.Deposit
import com.leonardovee.effectivesuccotash.domain.model.Withdraw
import com.leonardovee.effectivesuccotash.domain.usecase.CreateTransactionUseCase
import com.leonardovee.effectivesuccotash.presentation.resource.TransactionRequestResource
import com.leonardovee.effectivesuccotash.presentation.resource.TransactionResponseResource
import org.springframework.web.bind.annotation.PostMapping
import org.springframework.web.bind.annotation.RequestBody
import org.springframework.web.bind.annotation.RestController
import javax.validation.Valid

@RestController
class TransactionController(private val createTransactionUseCase: CreateTransactionUseCase) {
    @PostMapping("/transactions")
    fun create(@Valid @RequestBody transactionRequestResource: TransactionRequestResource): TransactionResponseResource {
        val deposit = Deposit(transactionRequestResource.payee, transactionRequestResource.value, null)
        val withdraw = Withdraw(transactionRequestResource.payer, transactionRequestResource.value, null)
        val transaction = createTransactionUseCase.execute(deposit, withdraw)
        return TransactionResponseResource(transaction.id)
    }
}