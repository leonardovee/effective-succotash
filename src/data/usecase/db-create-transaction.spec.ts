import { DbCreateTransaction } from '@/data/usecase/db-create-transaction'
import { UserModel } from '@/domain/model/user'
import { LoadUserByIdRepository } from '@/data/protocol/load-user-by-id-repository'
import { UnauthorizedTransactionError } from '@/error/unauthorized-transaction-error'

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

describe('DbCreateTransaction', () => {
  it('Should call user repository with correct values', async () => {
    const loadUserByIdRepositoryStub = makeLoadUserByIdRepository()
    const sut = new DbCreateTransaction(loadUserByIdRepositoryStub)
    const loadByIdSpy = jest.spyOn(loadUserByIdRepositoryStub, 'loadById')
    await sut.create({
      user: 'any_user',
      amount: 1000
    }, {
      user: 'other_user',
      amount: 1000
    })
    expect(loadByIdSpy).toHaveBeenCalledWith('other_user')
  })

  it('Should throw unauthorized transaction error if payer is bussiness type', async () => {
    const loadUserByIdRepositoryStub = makeLoadUserByIdRepository()
    const sut = new DbCreateTransaction(loadUserByIdRepositoryStub)
    jest.spyOn(loadUserByIdRepositoryStub, 'loadById').mockReturnValueOnce(
      new Promise(resolve => resolve({
        id: 'any_id',
        name: 'any_name',
        type: 'bussiness',
        email: 'any_email',
        password: 'any_password'
      }))
    )
    const promise = sut.create({
      user: 'any_user',
      amount: 1000
    }, {
      user: 'other_user',
      amount: 1000
    })
    await expect(promise).rejects.toThrow(UnauthorizedTransactionError)
  })
})
