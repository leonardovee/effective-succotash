import { DbCreateTransaction } from '@/data/usecase/db-create-transaction'
import { UserModel } from '@/domain/model/user'
import { LoadUserByIdRepository } from '@/data/protocol/load-user-by-id-repository'
import { UnauthorizedTransactionError } from '@/error/unauthorized-transaction-error'
import { DepositModel } from '@/domain/model/deposit'
import { WithdrawModel } from '@/domain/model/withdraw'
import { LoadWithdrawsByUserRepository } from '../protocol/load-withdraws-by-user-repository'

const makeFakeWithdraw = (): WithdrawModel => {
  return {
    user: 'any_id',
    amount: 1000
  }
}

const makeFakeRequest = (): [DepositModel, WithdrawModel] => {
  return [{
    user: 'any_user',
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
}

const makeSut = (): SutTypes => {
  const loadWithdrawsByUserRepositoryStub = makeLoadWithdrawsByUserRepository()
  const loadUserByIdRepositoryStub = makeLoadUserByIdRepository()
  const sut = new DbCreateTransaction(
    loadUserByIdRepositoryStub,
    loadWithdrawsByUserRepositoryStub
  )
  return {
    sut,
    loadUserByIdRepositoryStub,
    loadWithdrawsByUserRepositoryStub
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
})
