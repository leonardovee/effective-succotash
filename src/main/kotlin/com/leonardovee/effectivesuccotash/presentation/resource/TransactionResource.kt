package com.leonardovee.effectivesuccotash.presentation.resource

import org.jetbrains.annotations.NotNull

data class TransactionResource(
    @NotNull val payer: String,
    @NotNull val payee: String,
    @NotNull val value: String
)