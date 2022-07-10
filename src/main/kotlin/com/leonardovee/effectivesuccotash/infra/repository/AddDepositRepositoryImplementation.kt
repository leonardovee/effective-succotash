package com.leonardovee.effectivesuccotash.infra.repository

import com.leonardovee.effectivesuccotash.data.usecase.AddDepositRepository
import com.leonardovee.effectivesuccotash.domain.model.Deposit
import com.leonardovee.effectivesuccotash.infra.factory.ConnectionFactory
import org.springframework.stereotype.Component
import java.util.*

@Component
class AddDepositRepositoryImplementation(private val connectionFactory: ConnectionFactory) : AddDepositRepository {
    private val conn = connectionFactory.build()
    override fun addDeposit(deposit: Deposit): Deposit {
        val query = "INSERT INTO deposits (\"id\", \"user\", \"value\") VALUES (?, ?, ?)"
        val statement = conn.prepareStatement(query)

        deposit.id = UUID.randomUUID().toString()

        statement.setString(1, deposit.id)
        statement.setString(2, deposit.user)
        statement.setString(3, deposit.value)
        statement.execute()

        return deposit
    }
}