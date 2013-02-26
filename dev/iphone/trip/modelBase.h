//
//  modelBase.h
//  trip
//
//  Created by 沈 吾苓 on 13-2-5.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "toolLogger.h"


@interface modelBase : NSObject

@property (assign , nonatomic) NSString *_error;

@property (assign , nonatomic) NSDictionary *_dataDic;
@property (assign , nonatomic) NSString *_dataStr;

- (NSDictionary *) getDataDictionary;
- (NSString *)getError;

- (void)log:(NSString *)string;
@end
