//
//  modelBase.m
//  trip
//
//  Created by 沈 吾苓 on 13-2-5.
//  Copyright (c) 2013年 沈 吾苓. All rights reserved.
//

#import "modelBase.h"

@implementation modelBase

@synthesize _error;
@synthesize _dataDic;
@synthesize _dataStr;

- (NSDictionary *)getDataDictionary
{
    return _dataDic;
}
- (NSString *)getError
{
    return _error;
}

- (void)log:(NSString *)string
{
    [toolLogger debugLog:string type:(NSString *)[self class]];
}

@end
