import { DepositModel } from '@/domain/model/deposit'
import { TransactionModel } from '@/domain/model/transaction'
import { WithdrawModel } from '@/domain/model/withdraw'
import { CreateTransaction } from '@/domain/usecase/create-transaction'
import { LoadUserByIdRepository } from '@/data/protocol/load-user-by-id-repository'
import { UnauthorizedTransactionError } from '@/error/unauthorized-transaction-error'
import { LoadWithdrawsByUserRepository } from '@/data/protocol/load-withdraws-by-user-repository'
import { LoadDepositsByUserRepository } from '@/data/protocol/load-deposits-by-user-repository'

export class DbCreateTransaction implements CreateTransaction {
  constructor (
    private readonly loadUserByIdRepository: LoadUserByIdRepository,
    private readonly loadWithdrawsByUserRepository: LoadWithdrawsByUserRepository,
    private readonly loadDepositsByUserRepository: LoadDepositsByUserRepository
  ) {}

  async create (deposit: DepositModel, withdraw: WithdrawModel): Promise<TransactionModel> {
    const payer = await this.loadUserByIdRepository.loadById(withdraw.user)
    if (payer.type === 'bussiness') throw new UnauthorizedTransactionError()
    this.loadWithdrawsByUserRepository.loadByUser(payer.id)
    this.loadDepositsByUserRepository.loadByUser(deposit.user)
  }
}
