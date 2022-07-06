package com.leonardovee.effectivesuccotash.data.usecase

import com.leonardovee.effectivesuccotash.domain.model.Withdraw

interface AddWithdrawRepository {
    fun addWithdraw(withdraw: Withdraw): Withdraw
}