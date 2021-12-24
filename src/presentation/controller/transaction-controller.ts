import { Controller } from '@/presentation/protocol/controller'
import { HttpRequest, HttpResponse } from '@/presentation/protocol/http'
import { unprocessableEntity } from '@/presentation/http/unprocessable-entity'
import { MissingParamError } from '@/presentation/error/missing-param-error'

export class TransactionController implements Controller {
  async handle (httpRequest: HttpRequest): Promise<HttpResponse> {
    const { payer } = httpRequest.body
    if (!payer) {
      return unprocessableEntity(new MissingParamError('payer'))
    }
  }
}
