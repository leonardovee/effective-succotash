package com.leonardovee.effectivesuccotash.data.usecase

import com.leonardovee.effectivesuccotash.domain.model.Transaction
import org.springframework.stereotype.Component

@Component
interface FindTransactionRepository {
    fun findTransaction(id: String): Transaction
}