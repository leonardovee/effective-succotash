import { TransactionController } from '@/presentation/controller/transaction-controller'
import { unprocessableEntity } from '@/presentation/http/unprocessable-entity'
import { MissingParamError } from '@/presentation/error/missing-param-error'

describe('TransactionController', () => {
  it('Should return 422 if no payer is provided', async () => {
    const sut = new TransactionController()
    const response = await sut.handle({
      body: {
        payee: 'any_payee',
        amount: 1000
      }
    })
    expect(response).toEqual(unprocessableEntity(new MissingParamError('payer')))
  })
})
