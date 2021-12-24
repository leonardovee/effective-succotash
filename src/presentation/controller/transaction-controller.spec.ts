import { TransactionController } from '@/presentation/controller/transaction-controller'
import { unprocessableEntity } from '@/presentation/http/unprocessable-entity'
import { MissingParamError } from '@/presentation/error/missing-param-error'
import { DepositModel } from '@/domain/model/deposit'
import { TransactionModel } from '@/domain/model/transaction'
import { WithdrawModel } from '@/domain/model/withdraw'
import { CreateTransaction } from '@/domain/usecase/create-transaction'
import { internalServerError } from '../http/internal-server-error'

const makeFakeTransaction = (): TransactionModel => {
  return {
    id: 'any_transaction',
    deposit: makeFakeDeposit(),
    withdraw: makeFakeWithdraw()
  }
}

const makeFakeDeposit = (): DepositModel => {
  return {
    user: 'any_payer',
    amount: 1000
  }
}

const makeFakeWithdraw = (): WithdrawModel => {
  return {
    user: 'any_payee',
    amount: 1000
  }
}

const makeCreateTransaction = (): CreateTransaction => {
  class CreateTransactionStub implements CreateTransaction {
    async create(deposit: DepositModel, withdraw: WithdrawModel): Promise<TransactionModel> {
      return new Promise(resolve => resolve(makeFakeTransaction()))
    }
  }
  return new CreateTransactionStub()
}

interface SutTypes {
  sut: TransactionController
  createTransactionStub: CreateTransaction
}

const makeSut = (): SutTypes => {
  const createTransactionStub = makeCreateTransaction()
  const sut = new TransactionController(createTransactionStub)
  return {
    sut,
    createTransactionStub
  }
}

describe('TransactionController', () => {
  it('Should return 422 if no payer is provided', async () => {
    const { sut } = makeSut()
    const response = await sut.handle({
      body: {
        payee: 'any_payee',
        amount: 1000
      }
    })
    expect(response).toEqual(unprocessableEntity(new MissingParamError('payer')))
  })

  it('Should return 422 if no payee is provided', async () => {
    const { sut } = makeSut()
    const response = await sut.handle({
      body: {
        payer: 'any_payer',
        amount: 1000
      }
    })
    expect(response).toEqual(unprocessableEntity(new MissingParamError('payee')))
  })

  it('Should return 422 if no amount is provided', async () => {
    const { sut } = makeSut()
    const response = await sut.handle({
      body: {
        payer: 'any_payer',
        payee: 'any_payee'
      }
    })
    expect(response).toEqual(unprocessableEntity(new MissingParamError('amount')))
  })

  it('Should call CreateTransaction with correct values', async () => {
    const { sut, createTransactionStub } = makeSut()
    const createSpy = jest.spyOn(createTransactionStub, 'create')
    await sut.handle({
      body: {
        payer: 'any_payer',
        payee: 'any_payee',
        amount: 1000
      }
    })
    expect(createSpy).toHaveBeenCalledWith(makeFakeDeposit(), makeFakeWithdraw())
  })

  it('Should return 500 if CreateTransaction throws', async () => {
    const { sut, createTransactionStub } = makeSut()
    jest.spyOn(createTransactionStub, 'create').mockReturnValueOnce(
      new Promise((resolve, reject) => reject(new Error('any_message')))
    )
    const response = await sut.handle({
      body: {
        payer: 'any_payer',
        payee: 'any_payee',
        amount: 1000
      }
    })
    expect(response).toEqual(internalServerError(new Error('any_message')))
  })
})
