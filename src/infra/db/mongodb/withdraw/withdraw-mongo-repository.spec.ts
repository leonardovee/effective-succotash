import { MongoHelper } from '@/infra/db/mongodb/helper/mongo-helper'
import { Collection } from 'mongodb'
import { WithdrawMongoRepository } from '@/infra/db/mongodb/withdraw/withdraw-mongo-repository'

let withdrawCollection: Collection

const makeSut = (): WithdrawMongoRepository => new WithdrawMongoRepository()

describe('WithdrawMongoRepository', () => {
  beforeAll(async () => {
    await MongoHelper.connect(process.env.MONGO_URL ?? '')
  })

  afterAll(async () => {
    await MongoHelper.disconnect()
  })

  beforeEach(async () => {
    withdrawCollection = await MongoHelper.getCollection('withdraws')
    await withdrawCollection.deleteMany({})
  })

  describe('loadByUser()', () => {
    test('Should return the withdraws of the user on loadByUser success', async () => {
      await withdrawCollection.insertMany([
        { user: 'other_id', amount: 1000 },
        { user: 'any_id', amount: 1000 },
        { user: 'any_id', amount: 2000 },
        { user: 'any_id', amount: 3000 }
      ])
      const sut = makeSut()

      const withdraws = await sut.loadByUser('any_id')

      expect(withdraws.length).toBe(3)

      expect(withdraws[0].user).toBe('any_id')
      expect(withdraws[0].amount).toBe(1000)

      expect(withdraws[1].user).toBe('any_id')
      expect(withdraws[1].amount).toBe(2000)

      expect(withdraws[2].user).toBe('any_id')
      expect(withdraws[2].amount).toBe(3000)
    })
  })
})
