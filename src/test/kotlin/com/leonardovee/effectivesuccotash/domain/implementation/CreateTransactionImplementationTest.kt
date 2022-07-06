package com.leonardovee.effectivesuccotash.domain.implementation

import com.leonardovee.effectivesuccotash.data.usecase.AddDepositRepository
import com.leonardovee.effectivesuccotash.data.usecase.AddTransactionRepository
import com.leonardovee.effectivesuccotash.data.usecase.AddWithdrawRepository
import com.leonardovee.effectivesuccotash.domain.model.Deposit
import com.leonardovee.effectivesuccotash.domain.model.Transaction
import com.leonardovee.effectivesuccotash.domain.model.Withdraw
import com.leonardovee.effectivesuccotash.presentation.resource.TransactionRequestResource
import org.junit.jupiter.api.Assertions.assertEquals
import org.junit.jupiter.api.BeforeEach
import org.junit.jupiter.api.Test
import org.mockito.Mockito.*

internal class CreateTransactionImplementationTest {
    private var addTransactionRepository = mock(AddTransactionRepository::class.java)
    private var addWithdrawRepository = mock(AddWithdrawRepository::class.java)
    private var addDepositRepository = mock(AddDepositRepository::class.java)
    private var createTransactionImplementation =
        CreateTransactionImplementation(addDepositRepository, addWithdrawRepository, addTransactionRepository)
    private var transactionRequestResource = TransactionRequestResource("any@email.com", "other@email.com", "10.00")
    private val deposit = Deposit(transactionRequestResource.payee, transactionRequestResource.value, null)
    private val withdraw = Withdraw(transactionRequestResource.payer, transactionRequestResource.value, null)
    private val addedDeposit = Deposit(deposit.user, deposit.value, "a1901417-dadf-46bb-846c-4f84b4e29e13")
    private val addedWithdraw = Withdraw(withdraw.user, withdraw.value, "b1bec7d7-87d9-45e9-ab37-770e28f53885")
    private val addedTransaction =
        Transaction("a4261502-b0ac-4c0f-af39-f50d7bfd46cf", addedDeposit.id.toString(), addedWithdraw.id.toString())

    @BeforeEach
    fun setUp() {
        `when`(addDepositRepository.addDeposit(deposit)).thenReturn(addedDeposit)
        `when`(addWithdrawRepository.addWithdraw(withdraw)).thenReturn(addedWithdraw)
        `when`(addTransactionRepository.addTransaction(addedDeposit, addedWithdraw)).thenReturn(addedTransaction)
    }

    @Test
    fun `should call add deposit repository with correct values`() {
        createTransactionImplementation.execute(deposit, withdraw)
        verify(addDepositRepository, times(1)).addDeposit(deposit)
    }

    @Test
    fun `should call add withdraw repository with correct values`() {
        createTransactionImplementation.execute(deposit, withdraw)
        verify(addWithdrawRepository, times(1)).addWithdraw(withdraw)
    }

    @Test
    fun `should call add transaction repository with correct values`() {
        createTransactionImplementation.execute(deposit, withdraw)
        verify(addTransactionRepository, times(1)).addTransaction(addedDeposit, addedWithdraw)
    }

    @Test
    fun `should return transaction values`() {
        val result = createTransactionImplementation.execute(deposit, withdraw)
        assertEquals(addedTransaction, result)
    }
}