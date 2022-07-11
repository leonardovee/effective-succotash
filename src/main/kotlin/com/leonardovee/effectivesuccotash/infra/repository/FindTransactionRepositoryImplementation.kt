package com.leonardovee.effectivesuccotash.infra.repository

import com.leonardovee.effectivesuccotash.data.usecase.FindTransactionRepository
import com.leonardovee.effectivesuccotash.domain.model.Transaction
import com.leonardovee.effectivesuccotash.infra.factory.ConnectionFactory
import org.springframework.stereotype.Component

@Component
class FindTransactionRepositoryImplementation(connectionFactory: ConnectionFactory) :
    FindTransactionRepository {
    private val conn = connectionFactory.build()
    override fun findTransaction(id: String): Transaction {
        val query = "SELECT id, deposit, withdraw FROM transactions WHERE id = ? LIMIT 1"
        val statement = conn.prepareStatement(query)

        statement.setString(1, id)
        val result = statement.executeQuery()

        val transactions = mutableListOf<Transaction>()
        while (result.next()) {
            val transaction = result.getString("id")
            val deposit = result.getString("deposit")
            val withdraw = result.getString("withdraw")

            transactions.add(Transaction(transaction, deposit, withdraw))
        }

        return transactions.first()
    }
}