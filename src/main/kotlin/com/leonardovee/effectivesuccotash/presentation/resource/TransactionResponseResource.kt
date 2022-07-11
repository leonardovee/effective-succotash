package com.leonardovee.effectivesuccotash.presentation.resource

import org.jetbrains.annotations.NotNull

data class TransactionResponseResource(
    @NotNull val id: String,
    @NotNull val deposit: String,
    @NotNull val withdraw: String
)