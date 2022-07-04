package com.leonardovee.effectivesuccotash.presentation.controller

import org.springframework.web.bind.annotation.PostMapping
import org.springframework.web.bind.annotation.RestController

@RestController
class TransactionController {
    @PostMapping("/transactions")
    fun create() {}
}