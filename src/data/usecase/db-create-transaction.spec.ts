import { DbCreateTransaction } from '@/data/usecase/db-create-transaction'
import { UserModel } from '@/domain/model/user'
import { LoadUserByIdRepository } from '@/data/protocol/load-user-by-id-repository'
import { UnauthorizedTransactionError } from '@/error/unauthorized-transaction-error'
import { DepositModel } from '@/domain/model/deposit'
import { WithdrawModel } from '@/domain/model/withdraw'

const makeFakeRequest = (): [DepositModel, WithdrawModel] => {
  return [{
    user: 'any_user',
    amount: 1000
  }, {
    user: 'other_user',
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
}

const makeSut = (): SutTypes => {
  const loadUserByIdRepositoryStub = makeLoadUserByIdRepository()
  const sut = new DbCreateTransaction(loadUserByIdRepositoryStub)
  return {
    sut,
    loadUserByIdRepositoryStub
  }
}

describe('DbCreateTransaction', () => {
  it('Should call user repository with correct values', async () => {
    const { sut, loadUserByIdRepositoryStub } = makeSut()
    const loadByIdSpy = jest.spyOn(loadUserByIdRepositoryStub, 'loadById')

    await sut.create(...makeFakeRequest())

    expect(loadByIdSpy).toHaveBeenCalledWith('other_user')
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
})
