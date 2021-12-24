import { LoadUserByEmailRepository } from '@/data/protocol/load-user-by-id-repository'
import { UserModel } from '@/domain/model/user'
import { MongoHelper } from '@/infra/db/mongodb/helper/mongo-helper'

export class UserMongoRepository implements LoadUserByEmailRepository {
  async loadByEmail (email: string): Promise<UserModel> {
    const userCollection = await MongoHelper.getCollection('users')
    const account = await userCollection.findOne({ email })
    return account && MongoHelper.map(account)
  }
}
