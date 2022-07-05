package com.leonardovee.effectivesuccotash.presentation.resource

import org.jetbrains.annotations.NotNull

data class TransactionRequestResource(
    @NotNull val payer: String,
    @NotNull val payee: String,
    @NotNull val value: String
)