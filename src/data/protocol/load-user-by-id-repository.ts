import { UserModel } from "@/domain/model/user";

export interface LoadUserByIdRepository {
  loadById: (id: string) => Promise<UserModel>
}
