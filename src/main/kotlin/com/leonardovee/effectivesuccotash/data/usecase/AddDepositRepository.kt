package com.leonardovee.effectivesuccotash.data.usecase

import com.leonardovee.effectivesuccotash.domain.model.Deposit
import org.springframework.stereotype.Component

@Component
interface AddDepositRepository {
    fun addDeposit(deposit: Deposit): Deposit
}