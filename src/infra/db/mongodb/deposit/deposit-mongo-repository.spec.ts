import { MongoHelper } from '@/infra/db/mongodb/helper/mongo-helper'
import { Collection } from 'mongodb'
import { DepositMongoRepository } from '@/infra/db/mongodb/deposit/deposit-mongo-repository'

let depositCollection: Collection

const makeSut = (): DepositMongoRepository => new DepositMongoRepository()

describe('DepositMongoRepository', () => {
  beforeAll(async () => {
    await MongoHelper.connect(process.env.MONGO_URL ?? '')
  })

  afterAll(async () => {
    await MongoHelper.disconnect()
  })

  beforeEach(async () => {
    depositCollection = await MongoHelper.getCollection('deposits')
    await depositCollection.deleteMany({})
  })

  describe('loadByUser()', () => {
    test('Should return the deposits of the user on loadByUser success', async () => {
      await depositCollection.insertMany([
        { user: 'other_id', amount: 1000 },
        { user: 'any_id', amount: 1000 },
        { user: 'any_id', amount: 2000 },
        { user: 'any_id', amount: 3000 }
      ])
      const sut = makeSut()

      const deposits = await sut.loadByUser('any_id')

      expect(deposits.length).toBe(3)

      expect(deposits[0].user).toBe('any_id')
      expect(deposits[0].amount).toBe(1000)

      expect(deposits[1].user).toBe('any_id')
      expect(deposits[1].amount).toBe(2000)

      expect(deposits[2].user).toBe('any_id')
      expect(deposits[2].amount).toBe(3000)
    })

    test('Should return an empty list', async () => {
      await depositCollection.insertMany([
        { user: 'other_id', amount: 1000 }
      ])

      const sut = makeSut()

      const deposits = await sut.loadByUser('any_id')

      expect(deposits.length).toBe(0)
    })
  })
})
