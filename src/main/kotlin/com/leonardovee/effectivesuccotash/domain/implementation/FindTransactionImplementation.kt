package com.leonardovee.effectivesuccotash.domain.implementation

import com.leonardovee.effectivesuccotash.data.usecase.FindTransactionRepository
import com.leonardovee.effectivesuccotash.domain.model.Transaction
import com.leonardovee.effectivesuccotash.domain.usecase.FindTransactionUseCase
import org.springframework.stereotype.Component

@Component
class FindTransactionImplementation(private val findTransactionRepository: FindTransactionRepository) :
    FindTransactionUseCase {
    override fun execute(id: String): Transaction {
        return findTransactionRepository.findTransaction(id)
    }
}