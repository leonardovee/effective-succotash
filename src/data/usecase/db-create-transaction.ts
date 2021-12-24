import { DepositModel } from '@/domain/model/deposit'
import { TransactionModel } from '@/domain/model/transaction'
import { WithdrawModel } from '@/domain/model/withdraw'
import { CreateTransaction } from '@/domain/usecase/create-transaction'
import { LoadUserByIdRepository } from '@/data/protocol/load-user-by-id-repository'
import { UnauthorizedTransactionError } from '@/error/unauthorized-transaction-error'

export class DbCreateTransaction implements CreateTransaction {
  constructor (
    private readonly loadUserByIdRepository: LoadUserByIdRepository
  ) {}

  async create (deposit: DepositModel, withdraw: WithdrawModel): Promise<TransactionModel> {
    const payer = await this.loadUserByIdRepository.loadById(withdraw.user)
    if (payer.type === 'bussiness') throw new UnauthorizedTransactionError()
  }
}
