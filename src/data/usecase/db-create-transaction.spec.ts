import { DbCreateTransaction } from '@/data/usecase/db-create-transaction'
import { UserModel } from '@/domain/model/user'
import { LoadUserByIdRepository } from '@/data/protocol/load-user-by-id-repository'
import { UnauthorizedTransactionError } from '@/error/unauthorized-transaction-error'
import { DepositModel } from '@/domain/model/deposit'
import { WithdrawModel } from '@/domain/model/withdraw'
import { LoadWithdrawsByUserRepository } from '@/data/protocol/load-withdraws-by-user-repository'
import { LoadDepositsByUserRepository } from '@/data/protocol/load-deposits-by-user-repository'
import { AuthorizerRepository } from '../protocol/authorizer-repository'

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

const makeLoadUserByIdRepository = (): LoadUserByIdRepository => {
  class LoadUserByIdRepositoryStub implements LoadUserByIdRepository {
    async loadById (id: string): Promise<UserModel> {
      return new Promise(resolve => resolve(makeFakeUser()))
    }
  }
  return new LoadUserByIdRepositoryStub()
}

interface SutTypes {
  sut: DbCreateTransaction,
  loadUserByIdRepositoryStub: LoadUserByIdRepository
  loadWithdrawsByUserRepositoryStub: LoadWithdrawsByUserRepository
  loadDepositsByUserRepositoryStub: LoadDepositsByUserRepository
  authorizerRepositoryStub: AuthorizerRepository
}

const makeSut = (): SutTypes => {
  const authorizerRepositoryStub = makeAuthorizerRepository()
  const loadDepositsByUserRepositoryStub = makeLoadDepositsByUserRepository()
  const loadWithdrawsByUserRepositoryStub = makeLoadWithdrawsByUserRepository()
  const loadUserByIdRepositoryStub = makeLoadUserByIdRepository()
  const sut = new DbCreateTransaction(
    loadUserByIdRepositoryStub,
    loadWithdrawsByUserRepositoryStub,
    loadDepositsByUserRepositoryStub,
    authorizerRepositoryStub
  )
  return {
    sut,
    loadUserByIdRepositoryStub,
    loadWithdrawsByUserRepositoryStub,
    loadDepositsByUserRepositoryStub,
    authorizerRepositoryStub
  }
}

describe('DbCreateTransaction', () => {
  it('Should call user repository with correct values', async () => {
    const { sut, loadUserByIdRepositoryStub } = makeSut()
    const loadByIdSpy = jest.spyOn(loadUserByIdRepositoryStub, 'loadById')

    await sut.create(...makeFakeRequest())

    expect(loadByIdSpy).toHaveBeenCalledWith('any_id')
  })

  it('Should throw unauthorized transaction error if payer is bussiness type', async () => {
    const { sut, loadUserByIdRepositoryStub } = makeSut()
    jest.spyOn(loadUserByIdRepositoryStub, 'loadById').mockReturnValueOnce(
      new Promise(resolve => resolve({
        id: 'any_id',
        name: 'any_name',
        type: 'bussiness',
        email: 'any_email',
        password: 'any_password'
      }))
    )

    const promise = sut.create(...makeFakeRequest())

    await expect(promise).rejects.toThrow(UnauthorizedTransactionError)
  })

  it('Should call withdraw repository with correct values', async () => {
    const { sut, loadWithdrawsByUserRepositoryStub } = makeSut()
    const loadByIdSpy = jest.spyOn(loadWithdrawsByUserRepositoryStub, 'loadByUser')

    await sut.create(...makeFakeRequest())

    expect(loadByIdSpy).toHaveBeenCalledWith('any_id')
  })

  it('Should call deposit repository with correct values', async () => {
    const { sut, loadDepositsByUserRepositoryStub } = makeSut()
    const loadByIdSpy = jest.spyOn(loadDepositsByUserRepositoryStub, 'loadByUser')

    await sut.create(...makeFakeRequest())

    expect(loadByIdSpy).toHaveBeenCalledWith('other_id')
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

    await expect(promise).rejects.toThrow(UnauthorizedTransactionError)
  })

  it('Should call authorizer repository with correct values', async () => {
    const { sut, authorizerRepositoryStub } = makeSut()
    const loadByIdSpy = jest.spyOn(authorizerRepositoryStub, 'authorize')

    await sut.create(...makeFakeRequest())

    expect(loadByIdSpy).toHaveBeenCalledWith(...makeFakeRequest())
  })
})
