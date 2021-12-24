import { UserModel } from '@/domain/model/user'

export interface LoadUserByEmailRepository {
  loadByEmail: (email: string) => Promise<UserModel>
}
