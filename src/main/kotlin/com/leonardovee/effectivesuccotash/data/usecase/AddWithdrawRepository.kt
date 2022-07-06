package com.leonardovee.effectivesuccotash.data.usecase

import com.leonardovee.effectivesuccotash.domain.model.Withdraw
import org.springframework.stereotype.Component

@Component
interface AddWithdrawRepository {
    fun addWithdraw(withdraw: Withdraw): Withdraw
}