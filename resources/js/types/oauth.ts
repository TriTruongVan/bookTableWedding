interface OAuthCredentialInterface {
  id?: number;
  appId: string;
  secret?: string;
  status?: 'active' | 'inactive' | 'expired';
  createdAt?: string;
  updatedAt?: string;
  expiredAt?: string;
}

export type { OAuthCredentialInterface };
