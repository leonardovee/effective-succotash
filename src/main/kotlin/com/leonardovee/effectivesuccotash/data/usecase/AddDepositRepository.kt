package com.leonardovee.effectivesuccotash.data.usecase

import com.leonardovee.effectivesuccotash.domain.model.Deposit

interface AddDepositRepository {
    fun addDeposit(deposit: Deposit): Deposit
}