package com.leonardovee.effectivesuccotash.domain.usecase

import com.leonardovee.effectivesuccotash.domain.model.Transaction
import org.springframework.stereotype.Component

@Component
interface FindTransactionUseCase {
    fun execute(id: String): Transaction
}