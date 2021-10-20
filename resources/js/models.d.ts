declare namespace App.Models {
    export interface Distributor {
        id: number;
        name: string;
        updating: boolean;
        last_updated: string | null;
        products_class: string;
        total_products: number;
        created_at: string | null;
        updated_at: string | null;
    }
    export interface PhoenixPharmaProduct {
        id: number;
        articleId: number;
        articleNumber: number;
        cyrName: string;
        latName: string;
        measureName: string;
        producerCode: string;
        producerName: string;
        basePrice: number;
        salePrice: number;
        maxPrice: number;
        nhifCode: string;
        nhifBasePrice: number;
        nhifSalePrice: number;
        nhifMaxPrice: number;
        isMedicalPrescription: boolean;
        isWebSaleProhibition: boolean;
        isDrugstoreAllowed: boolean;
        isDrug: boolean;
        isForRefrigerator: boolean;
        advertismentText: string;
        barcode1: string;
        barcode2: string;
        description: string;
        overrateGroupID: number;
        lastupdate: string;
        isActive: boolean;
        deleted: boolean;
        created_at: string | null;
        updated_at: string | null;
    }
    export interface User {
        id: number;
        name: string;
        email: string;
        email_verified_at: string | null;
        password: string;
        remember_token: string | null;
        created_at: string | null;
        updated_at: string | null;
    }
}