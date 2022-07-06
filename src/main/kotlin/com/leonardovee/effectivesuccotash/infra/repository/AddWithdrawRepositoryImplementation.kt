package com.leonardovee.effectivesuccotash.infra.repository

import com.leonardovee.effectivesuccotash.data.usecase.AddWithdrawRepository
import com.leonardovee.effectivesuccotash.domain.model.Withdraw
import org.springframework.stereotype.Component

@Component
class AddWithdrawRepositoryImplementation : AddWithdrawRepository {
    override fun addWithdraw(withdraw: Withdraw): Withdraw {
        return Withdraw("", "10.00", "")
    }
}