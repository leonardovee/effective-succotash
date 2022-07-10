package com.leonardovee.effectivesuccotash.infra.repository

import com.leonardovee.effectivesuccotash.data.usecase.AddWithdrawRepository
import com.leonardovee.effectivesuccotash.domain.model.Withdraw
import com.leonardovee.effectivesuccotash.infra.factory.ConnectionFactory
import org.springframework.stereotype.Component
import java.util.*

@Component
class AddWithdrawRepositoryImplementation(private val connectionFactory: ConnectionFactory) : AddWithdrawRepository {
    private val conn = connectionFactory.build()
    override fun addWithdraw(withdraw: Withdraw): Withdraw {
        val query = "INSERT INTO withdraws (\"id\", \"user\", \"value\") VALUES (?, ?, ?)"
        val statement = conn.prepareStatement(query)

        withdraw.id = UUID.randomUUID().toString()

        statement.setString(1, withdraw.id)
        statement.setString(2, withdraw.user)
        statement.setString(3, withdraw.value)
        statement.execute()

        return withdraw
    }
}