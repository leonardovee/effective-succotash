import { DbCreateTransaction } from '@/data/usecase/db-create-transaction'
import { UserModel } from '@/domain/model/user'
import { LoadUserByEmailRepository } from '@/data/protocol/load-user-by-id-repository'
import { DepositModel } from '@/domain/model/deposit'
import { WithdrawModel } from '@/domain/model/withdraw'
import { LoadWithdrawsByUserRepository } from '@/data/protocol/load-withdraws-by-user-repository'
import { LoadDepositsByUserRepository } from '@/data/protocol/load-deposits-by-user-repository'
import { AuthorizerRepository } from '../protocol/authorizer-repository'
import { CreateTransactionByDepositAndWithdrawRepository } from '../protocol/create-transaction-by-deposit-and-withdraw-repository'
import { TransactionModel } from '@/domain/model/transaction'

const makeFakeTransaction = (): TransactionModel => {
  return {
    id: 'any_id',
    deposit: makeFakeDeposit(),
    withdraw: makeFakeWithdraw()
  }
}

const makeFakeDeposit = (): DepositModel => {
  return {
    user: 'other_id',
    amount: 1000
  }
}

const makeFakeWithdraw = (): WithdrawModel => {
  return {
    user: 'any_id',
    amount: 1000
  }
}

const makeFakeRequest = (): [DepositModel, WithdrawModel] => {
  return [{
    user: 'other_id',
    amount: 1000
  }, {
    user: 'any_id',
    amount: 1000
  }]
}

const makeFakeUser = (): UserModel => {
  return {
    id: 'any_id',
    name: 'any_name',
    type: 'user',
    email: 'any_email',
    password: 'any_password'
  }
}

const makeAuthorizerRepository = (): AuthorizerRepository => {
  class AuthorizerRepositoryStub implements AuthorizerRepository {
    async authorize (deposit: DepositModel, withdraw: WithdrawModel): Promise<boolean> {
      return new Promise(resolve => resolve(true))
    }
  }
  return new AuthorizerRepositoryStub()
}

const makeCreateTransactionByDepositAndWithdrawRepository = (): CreateTransactionByDepositAndWithdrawRepository => {
  class CreateTransactionByDepositAndWithdrawRepositoryStub implements CreateTransactionByDepositAndWithdrawRepository {
    async createByDepositAndWithdraw (deposit: DepositModel, withdraw: WithdrawModel): Promise<TransactionModel> {
      return new Promise(resolve => resolve(makeFakeTransaction()))
    }
  }
  return new CreateTransactionByDepositAndWithdrawRepositoryStub()
}

const makeLoadDepositsByUserRepository = (): LoadDepositsByUserRepository => {
  class LoadDepositsByUserRepositoryStub implements LoadDepositsByUserRepository {
    async loadByUser (id: string): Promise<WithdrawModel[]> {
      return new Promise(resolve => resolve([makeFakeDeposit(), makeFakeDeposit()]))
    }
  }
  return new LoadDepositsByUserRepositoryStub()
}

const makeLoadWithdrawsByUserRepository = (): LoadWithdrawsByUserRepository => {
  class LoadWithdrawsByUserRepositoryStub implements LoadWithdrawsByUserRepository {
    async loadByUser (id: string): Promise<WithdrawModel[]> {
      return new Promise(resolve => resolve([makeFakeWithdraw()]))
    }
  }
  return new LoadWithdrawsByUserRepositoryStub()
}

const makeLoadUserByEmailRepository = (): LoadUserByEmailRepository => {
  class LoadUserByEmailRepositoryStub implements LoadUserByEmailRepository {
    async loadByEmail (email: string): Promise<UserModel> {
      return new Promise(resolve => resolve(makeFakeUser()))
    }
  }
  return new LoadUserByEmailRepositoryStub()
}

interface SutTypes {
  sut: DbCreateTransaction,
  loadUserByEmailRepositoryStub: LoadUserByEmailRepository
  loadWithdrawsByUserRepositoryStub: LoadWithdrawsByUserRepository
  loadDepositsByUserRepositoryStub: LoadDepositsByUserRepository
  authorizerRepositoryStub: AuthorizerRepository,
  createTransactionByDepositAndWithdrawRepositoryStub: CreateTransactionByDepositAndWithdrawRepository
}

const makeSut = (): SutTypes => {
  const createTransactionByDepositAndWithdrawRepositoryStub = makeCreateTransactionByDepositAndWithdrawRepository()
  const authorizerRepositoryStub = makeAuthorizerRepository()
  const loadDepositsByUserRepositoryStub = makeLoadDepositsByUserRepository()
  const loadWithdrawsByUserRepositoryStub = makeLoadWithdrawsByUserRepository()
  const loadUserByEmailRepositoryStub = makeLoadUserByEmailRepository()
  const sut = new DbCreateTransaction(
    loadUserByEmailRepositoryStub,
    loadWithdrawsByUserRepositoryStub,
    loadDepositsByUserRepositoryStub,
    authorizerRepositoryStub,
    createTransactionByDepositAndWithdrawRepositoryStub
  )
  return {
    sut,
    loadUserByEmailRepositoryStub,
    loadWithdrawsByUserRepositoryStub,
    loadDepositsByUserRepositoryStub,
    authorizerRepositoryStub,
    createTransactionByDepositAndWithdrawRepositoryStub
  }
}

describe('DbCreateTransaction', () => {
  it('Should call user repository with correct values', async () => {
    const { sut, loadUserByEmailRepositoryStub } = makeSut()
    const loadByEmailSpy = jest.spyOn(loadUserByEmailRepositoryStub, 'loadByEmail')

    await sut.create(...makeFakeRequest())

    expect(loadByEmailSpy).toHaveBeenCalledWith('any_id')
  })

  it('Should throw unauthorized transaction error if payer is bussiness type', async () => {
    const { sut, loadUserByEmailRepositoryStub } = makeSut()
    jest.spyOn(loadUserByEmailRepositoryStub, 'loadByEmail').mockReturnValueOnce(
      new Promise(resolve => resolve({
        id: 'any_id',
        name: 'any_name',
        type: 'bussiness',
        email: 'any_email',
        password: 'any_password'
      }))
    )

    const promise = sut.create(...makeFakeRequest())

    await expect(promise).rejects.toThrow('Bussiness shoudn\'t pay users.')
  })

  it('Should call withdraw repository with correct values', async () => {
    const { sut, loadWithdrawsByUserRepositoryStub } = makeSut()
    const loadByEmailSpy = jest.spyOn(loadWithdrawsByUserRepositoryStub, 'loadByUser')

    await sut.create(...makeFakeRequest())

    expect(loadByEmailSpy).toHaveBeenCalledWith('any_id')
  })

  it('Should call deposit repository with correct values', async () => {
    const { sut, loadDepositsByUserRepositoryStub } = makeSut()
    const loadByEmailSpy = jest.spyOn(loadDepositsByUserRepositoryStub, 'loadByUser')

    await sut.create(...makeFakeRequest())

    expect(loadByEmailSpy).toHaveBeenCalledWith('other_id')
  })

  it('Should throw unauthorized transaction error if payer doesnt have enough balance', async () => {
    const { sut, loadWithdrawsByUserRepositoryStub, loadDepositsByUserRepositoryStub } = makeSut()
    jest.spyOn(loadWithdrawsByUserRepositoryStub, 'loadByUser').mockReturnValueOnce(
      new Promise(resolve => resolve([makeFakeWithdraw()]))
    )
    jest.spyOn(loadDepositsByUserRepositoryStub, 'loadByUser').mockReturnValueOnce(
      new Promise(resolve => resolve([makeFakeDeposit()]))
    )

    const promise = sut.create(...makeFakeRequest())

    await expect(promise).rejects.toThrow('Not enough balance.')
  })

  it('Should call authorizer repository with correct values', async () => {
    const { sut, authorizerRepositoryStub } = makeSut()
    const loadByEmailSpy = jest.spyOn(authorizerRepositoryStub, 'authorize')

    await sut.create(...makeFakeRequest())

    expect(loadByEmailSpy).toHaveBeenCalledWith(...makeFakeRequest())
  })

  it('Should throw unauthorized transaction error if authorizer repository returns false', async () => {
    const { sut, authorizerRepositoryStub } = makeSut()
    jest.spyOn(authorizerRepositoryStub, 'authorize').mockReturnValueOnce(
      new Promise(resolve => resolve(false))
    )

    const promise = sut.create(...makeFakeRequest())

    await expect(promise).rejects.toThrow('Transaction unauthorized.')
  })

  it('Should call transaction repository with correct values', async () => {
    const { sut, createTransactionByDepositAndWithdrawRepositoryStub } = makeSut()
    const loadByEmailSpy = jest.spyOn(createTransactionByDepositAndWithdrawRepositoryStub, 'createByDepositAndWithdraw')

    await sut.create(...makeFakeRequest())

    expect(loadByEmailSpy).toHaveBeenCalledWith(...makeFakeRequest())
  })

  it('Should return a transaction model', async () => {
    const { sut } = makeSut()

    const transaction = await sut.create(...makeFakeRequest())

    expect(transaction).toEqual(makeFakeTransaction())
  })
})
