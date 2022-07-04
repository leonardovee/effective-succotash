package com.leonardovee.effectivesuccotash.presentation.controller

import com.leonardovee.effectivesuccotash.presentation.resource.TransactionResource
import org.springframework.http.ResponseEntity
import org.springframework.web.bind.annotation.PostMapping
import org.springframework.web.bind.annotation.RequestBody
import org.springframework.web.bind.annotation.RestController
import javax.validation.Valid

@RestController
class TransactionController {
    @PostMapping("/transactions")
    fun create(@Valid @RequestBody transactionResource: TransactionResource): ResponseEntity<String> {
        return ResponseEntity.ok("")
    }
}