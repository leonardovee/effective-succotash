package com.leonardovee.effectivesuccotash.domain.implementation

import com.leonardovee.effectivesuccotash.data.usecase.FindTransactionRepository
import com.leonardovee.effectivesuccotash.domain.model.Transaction
import org.junit.jupiter.api.Assertions.assertEquals
import org.junit.jupiter.api.BeforeEach
import org.junit.jupiter.api.Test
import org.mockito.Mockito.*

internal class FindTransactionImplementationTest {
    private var findTransactionRepository = mock(FindTransactionRepository::class.java)
    private var findTransactionImplementation = FindTransactionImplementation(findTransactionRepository)
    private val foundTransaction = Transaction(
        "a4261502-b0ac-4c0f-af39-f50d7bfd46cf",
        "669cafbc-207a-46d7-880e-040bd65dd1a7",
        "e8cf0fcc-2476-458a-aea8-befda657b808"
    )

    @BeforeEach
    fun setUp() {
        `when`(findTransactionRepository.findTransaction("a4261502-b0ac-4c0f-af39-f50d7bfd46cf")).thenReturn(
            foundTransaction
        )
    }

    @Test
    fun `should call find transaction repository with correct values`() {
        findTransactionImplementation.execute("a4261502-b0ac-4c0f-af39-f50d7bfd46cf")
        verify(findTransactionRepository, times(1)).findTransaction("a4261502-b0ac-4c0f-af39-f50d7bfd46cf")
    }

    @Test
    fun `should return transaction values`() {
        val result = findTransactionImplementation.execute("a4261502-b0ac-4c0f-af39-f50d7bfd46cf")
        assertEquals(foundTransaction, result)
    }
}