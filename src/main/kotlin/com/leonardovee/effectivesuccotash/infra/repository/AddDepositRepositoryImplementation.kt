package com.leonardovee.effectivesuccotash.infra.repository

import com.leonardovee.effectivesuccotash.data.usecase.AddDepositRepository
import com.leonardovee.effectivesuccotash.domain.model.Deposit
import org.springframework.stereotype.Component

@Component
class AddDepositRepositoryImplementation : AddDepositRepository {
    override fun addDeposit(deposit: Deposit): Deposit {
        return Deposit("", "10.00", "")
    }
}