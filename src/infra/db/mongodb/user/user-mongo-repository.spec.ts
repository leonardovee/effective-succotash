import { UserMongoRepository } from '@/infra/db/mongodb/user/user-mongo-repository'
import { MongoHelper } from '@/infra/db/mongodb/helper/mongo-helper'
import { Collection } from 'mongodb'

let userCollection: Collection

const makeSut = (): UserMongoRepository => new UserMongoRepository()

describe('UserMongoRepository', () => {
  beforeAll(async () => {
    await MongoHelper.connect(process.env.MONGO_URL ?? '')
  })

  afterAll(async () => {
    await MongoHelper.disconnect()
  })

  beforeEach(async () => {
    userCollection = await MongoHelper.getCollection('users')
    await userCollection.deleteMany({})
  })

  describe('loadByEmail()', () => {
    test('Should return an user on loadById success', async () => {
      await userCollection.insertOne({
        name: 'any_name',
        type: 'any_type',
        email: 'any_email@mail.com',
        password: 'any_password'
      })
      const sut = makeSut()

      const user = await sut.loadByEmail('any_email@mail.com')

      expect(user.id).toBeTruthy()
      expect(user.name).toBe('any_name')
      expect(user.email).toBe('any_email@mail.com')
      expect(user.password).toBe('any_password')
    })

    test('Should return null on loadByEmail failure', async () => {
      const sut = makeSut()
      const account = await sut.loadByEmail('any_email@mail.com')
      expect(account).toBeFalsy()
    })
  })
})
