import { Controller } from '@/presentation/protocol/controller'
import { HttpRequest, HttpResponse } from '@/presentation/protocol/http'
import { unprocessableEntity } from '@/presentation/http/unprocessable-entity'
import { MissingParamError } from '@/presentation/error/missing-param-error'
import { CreateTransaction } from '@/domain/usecase/create-transaction'
import { internalServerError } from '../http/internal-server-error'
import { created } from '../http/created'

export class TransactionController implements Controller {
  constructor (
    private readonly createTransaction: CreateTransaction
  ) {}

  async handle (httpRequest: HttpRequest): Promise<HttpResponse> {
    const { payer, payee, amount } = httpRequest.body

    if (!payer) {
      return unprocessableEntity(new MissingParamError('payer'))
    }
    if (!payee) {
      return unprocessableEntity(new MissingParamError('payee'))
    }
    if (!amount) {
      return unprocessableEntity(new MissingParamError('amount'))
    }

    let transaction = null
    try {
      transaction = await this.createTransaction.create({
        user: payer,
        amount
      }, {
        user: payee,
        amount
      })
    } catch (error) {
      return internalServerError(error)
    }

    return created(transaction)
  }
}
