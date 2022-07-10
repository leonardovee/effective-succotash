package com.leonardovee.effectivesuccotash.infra.repository

import com.leonardovee.effectivesuccotash.data.usecase.AddTransactionRepository
import com.leonardovee.effectivesuccotash.domain.model.Deposit
import com.leonardovee.effectivesuccotash.domain.model.Transaction
import com.leonardovee.effectivesuccotash.domain.model.Withdraw
import com.leonardovee.effectivesuccotash.infra.factory.ConnectionFactory
import org.springframework.stereotype.Component
import java.util.*

@Component
class AddTransactionRepositoryImplementation(private val connectionFactory: ConnectionFactory) :
    AddTransactionRepository {
    private val conn = connectionFactory.build()
    override fun addTransaction(deposit: Deposit, withdraw: Withdraw): Transaction {
        val query = "INSERT INTO transactions (\"id\", \"deposit\", \"withdraw\") VALUES (?, ?, ?)"
        val statement = conn.prepareStatement(query)

        val transaction = Transaction(
            UUID.randomUUID().toString(),
            deposit.id.toString(),
            withdraw.id.toString()
        )

        statement.setString(1, transaction.id)
        statement.setString(2, transaction.deposit)
        statement.setString(3, transaction.withdraw)
        statement.execute()

        return transaction
    }
}